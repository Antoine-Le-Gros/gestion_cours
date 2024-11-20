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
export function updatePassword(id, newPassword) {
    return fetch(`${BASE_URL}/users/${id}`, {
        method: "PATCH",
        headers: {
            "Content-Type": "application/merge-patch+json", // Type de contenu envoyé
            "Accept": "*/*", // Accepte tous les types de contenu en réponse
        },
        body: JSON.stringify({ password: newPassword }),
    });
}



