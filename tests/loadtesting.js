import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  scenarios: {
    homepage: {
      executor: 'ramping-vus',
      exec: 'testHomepage',
      startVUs: 0,
      stages: [
        { duration: '10s', target: 10 },
        { duration: '10s', target: 25 },
        { duration: '10s', target: 50 },
        { duration: '10s', target: 75 },
        { duration: '10s', target: 100 },
        { duration: '20s', target: 100 },
        { duration: '10s', target: 0 },
      ],
      gracefulRampDown: '10s',
    },
    registration: {
      executor: 'ramping-vus',
      exec: 'testRegistration',
      startTime: '1m30s',
      startVUs: 0,
      stages: [
        { duration: '10s', target: 10 },
        { duration: '10s', target: 25 },
        { duration: '10s', target: 50 },
        { duration: '10s', target: 75 },
        { duration: '10s', target: 100 },
        { duration: '20s', target: 100 },
        { duration: '10s', target: 0 },
      ],
      gracefulRampDown: '10s',
    },
    authenticatedPages: {
      executor: 'ramping-vus',
      exec: 'testAuthenticatedPages',
      startTime: '3m0s',
      startVUs: 0,
      stages: [
        { duration: '15s', target: 5 },
        { duration: '15s', target: 10 },
        { duration: '15s', target: 20 },
        { duration: '15s', target: 35 },
        { duration: '15s', target: 100 },
        { duration: '20s', target: 100 },
        { duration: '10s', target: 0 },
      ],
      gracefulRampDown: '10s',
    },
  },
  thresholds: {
    http_req_failed: ['rate<0.20'],
    checks: ['rate>0.80'],
    http_req_duration: ['p(95)<5000'],
  },
  noConnectionReuse: false,
};


const BASE_URL = 'http://127.0.0.1:8000';
const EMAIL = 'test@example.com';
const PASSWORD = 'test';

function extractCsrfToken(html) {
  if (!html) return null;

  let match = html.match(/name="_token"\s+value="([^"]+)"/);

  if (!match) {
    match = html.match(/value="([^"]+)"\s+name="_token"/);
  }

  if (!match) {
    match = html.match(/<meta\s+name="csrf-token"\s+content="([^"]+)"/i);
  }

  if (!match) {
    match = html.match(/name=['"]_token['"][^>]*value=['"]([^'"]+)['"]/i);
  }

  if (!match) {
    match = html.match(/value=['"]([^'"]+)['"][^>]*name=['"]_token['"]/i);
  }

  return match ? match[1] : null;
}

function logResponse(label, res) {
  console.log(`${label} -> status=${res.status}, url=${res.url}`);
}

export function testHomepage() {
  const res = http.get(`${BASE_URL}/`, {
    timeout: '10s',
    redirects: 0,
    headers: {
      Accept: 'text/html',
    },
  });

  logResponse('HOMEPAGE', res);

  check(res, {
    'homepage returns 200 or redirect': (r) => r.status === 200 || r.status === 302,
  });

  sleep(1);
}

export function testRegistration() {
  const res = http.get(`${BASE_URL}/registration`, {
    timeout: '10s',
    redirects: 0,
    headers: {
      Accept: 'text/html',
    },
  });

  logResponse('REGISTRATION', res);

  check(res, {
    'registration returns 200 or redirect': (r) => r.status === 200 || r.status === 302,
  });

  sleep(1);
}

function login() {
  const jar = http.cookieJar();

  const loginPage = http.get(`${BASE_URL}/`, {
    timeout: '30s',
    redirects: 0,
    jar: jar,
    headers: {
      Accept: 'text/html',
    },
  });

  logResponse('LOGIN PAGE', loginPage);

  const csrfToken = extractCsrfToken(loginPage.body || '');

  check(loginPage, {
    'login page returns 200': (r) => r.status === 200,
  });

  check(csrfToken, {
    'csrf token found': (t) => t !== null,
  });

  if (!csrfToken) {
    console.log('Could not find CSRF token on login page.');
    return null;
  }

  const payload = {
    _token: csrfToken,
    email: EMAIL,
    password: PASSWORD,
  };

  const loginRes = http.post(`${BASE_URL}/verify-login-credentials`, payload, {
    timeout: '30s',
    redirects: 0,
    jar: jar,
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      Accept: 'text/html,application/json',
    },
  });

  logResponse('LOGIN POST', loginRes);

  check(loginRes, {
    'login response is 200 or 302': (r) => r.status === 200 || r.status === 302,
  });

  return jar;
}

export function testAuthenticatedPages() {
  const jar = login();

  if (!jar) {
    console.log('Skipping authenticated page test because login failed.');
    return;
  }

  const missionsListRes = http.get(`${BASE_URL}/missions-list`, {
    timeout: '30s',
    redirects: 0,
    jar: jar,
    headers: {
      Accept: 'text/html,application/json',
    },
  });

  logResponse('MISSIONS LIST', missionsListRes);

  check(missionsListRes, {
    'missions list returns 200 or redirect': (r) => r.status === 200 || r.status === 302,
  });

  const createMissionPageRes = http.get(`${BASE_URL}/create-mission`, {
    timeout: '10s',
    redirects: 0,
    jar: jar,
    headers: {
      Accept: 'text/html,application/json',
    },
  });

  logResponse('CREATE MISSION PAGE', createMissionPageRes);

  check(createMissionPageRes, {
    'create mission returns 200 or redirect': (r) => r.status === 200 || r.status === 302,
  });

  sleep(1);
}