import http from 'k6/http';
import { sleep } from 'k6';

export let options = {
    stages: [
        { duration: '1m', target: 100 }, // Va simuler 100 utilisateurs pendant 1 minute
    ]
};

export default function () {
    http.get('https://gesign.wstr.fr/');
    sleep(1);
}
