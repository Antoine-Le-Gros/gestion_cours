export const BASE_URL = '/api';

export function fetchUsers() {
    return fetch(`${BASE_URL}/users`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}
export function fetchMe() {
    return fetch(`${BASE_URL}/me`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );

}
