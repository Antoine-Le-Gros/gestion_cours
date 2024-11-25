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
            "Content-Type": "application/merge-patch+json",
            "Accept": "*/*",
        },
        body: JSON.stringify({ password: newPassword }),
    });
}
export function fetchUsersByRole(search, role){
    return fetch(`${BASE_URL}/users_teacher_list?search=${search}&role=${role}`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}
export function fetchRoles(){
    return fetch(`${BASE_URL}/roles`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}

export function fetchAllYears() {
    return fetch(`${BASE_URL}/years`)
        .then((response) => response.json())
        .then((data) => {

            return data["hydra:member"] || [];
        });
}

export function fetchAffecationByUserAndYear(userId, yearId) {
    return fetch(`${BASE_URL}/users/${userId}/${yearId}/affectations`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null),
    );
}
export function fetchCourseTitleInformation(search = "", semester, tag = 0) {
    if(!tag){
        return fetch(`${BASE_URL}/course_titles_information?search=${search}&semester=${semester}`).then((response) =>
            response.ok ? response.json() : Promise.resolve(null)
        );
    } else {
        return fetch(`${BASE_URL}/course_titles_information?search=${search}&tag=${tag}&semester=${semester}`).then((response) =>
            response.ok ? response.json() : Promise.resolve(null)
        );
    }
}
export function fetchTags() {
    return fetch(`${BASE_URL}/tags`).then((response) =>
        response.ok ? response.json() : Promise.resolve(null)
    );
}

export const fetchUserById = async (id) => {
    const response = await fetch(`/api/users/${id}`);
    if (!response.ok) {
        throw new Error(`Erreur lors de la récupération de l'utilisateur ${id}`);
    }
    return response.json();
};
