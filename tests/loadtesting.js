import http from 'k6/http';
import { sleep, check } from 'k6';

export const options = {
  vus: 100,      // 100 virtual users
  duration: '1m' // run for 1 minute
};

export default function () {
  const res = http.get('http://127.0.0.1:8000');

  check(res, {
    'status is 200': (r) => r.status === 200,
  });

  sleep(1);
}