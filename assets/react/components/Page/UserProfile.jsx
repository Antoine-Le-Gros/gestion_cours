import React, { useEffect, useState } from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import { fetchMe, updatePassword } from "../../services/api.js";
import PasswordChangeModal from "../Molecule/PassWordChangeModal.js";
import Toast from "../Molecule/Toast.js";

export default function UserProfile() {
    const [user, setUser] = useState({});
    const [isPopupOpen, setIsPopupOpen] = useState(false);
    const [toastMessage, setToastMessage] = useState(null); // État pour gérer le message Toast
    const [toastType, setToastType] = useState("success"); // success ou error

    useEffect(() => {
        fetchMe().then((data) => {
            setUser(data || {});
        });
    }, []);

    const handlePasswordChange = async (newPassword) => {
        try {
            if (!user.id) return;

            const response = await updatePassword(user.id, newPassword);
            if (response.ok) {
                setToastType("success");
                setToastMessage("Mot de passe mis à jour avec succès !");
                setIsPopupOpen(false);
            } else {
                setToastType("error");
                setToastMessage("Erreur lors de la mise à jour du mot de passe.");
            }
        } catch (error) {
            console.error("Erreur:", error);
            setToastType("error");
            setToastMessage("Une erreur est survenue.");
        }

        // Affiche le toast pendant 3 secondes, puis le masque
        setTimeout(() => setToastMessage(null), 3000);
    };

    return (
        <div className="container mt-5 d-flex justify-content-center align-items-center" style={{ minHeight: "100vh" }}>
            <div
                className="card shadow-lg border-0 rounded-lg"
                style={{
                    backgroundColor: "#f8f9fa",
                    width: "80%",
                    minHeight: "65vh",
                }}
            >
                <div className="card-header bg-black text-white text-center">
                    <h3>Profil Utilisateur</h3>
                </div>
                <div className="card-body p-5">
                    <div className="row mb-4">
                        <div className="col-md-4">
                            <h5 className="text-secondary">Identifiant :</h5>
                            <p className="text-dark fw-bold fs-5">{user.login || "Non renseigné"}</p>
                        </div>
                        <div className="col-md-4">
                            <h5 className="text-secondary">Nom :</h5>
                            <p className="text-dark fw-bold fs-5">{user.lastname || "Non renseigné"}</p>
                        </div>
                        <div className="col-md-4">
                            <h5 className="text-secondary">Prénom :</h5>
                            <p className="text-dark fw-bold fs-5">{user.firstname || "Non renseigné"}</p>
                        </div>
                    </div>
                    <div className="row mb-4">
                        <div className="col-md-6">
                            <h5 className="text-secondary">Statut du compte :</h5>
                            <span
                                className={`badge ${
                                    user.isActive ? "bg-success" : "bg-danger"
                                } fs-6`}
                            >
                                {user.isActive ? "Actif" : "Inactif"}
                            </span>
                        </div>
                        <div className="col-md-6 text-end">
                            <h5 className="text-secondary">Mot de passe :</h5>
                            <button
                                className="btn btn-dark text-white"
                                onClick={() => setIsPopupOpen(true)}
                            >
                                Changer de mot de passe
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {/* Modal pour changer le mot de passe */}
            {isPopupOpen && (
                <PasswordChangeModal
                    onClose={() => setIsPopupOpen(false)}
                    onPasswordChange={handlePasswordChange}
                />
            )}

            {/* Toast pour les messages */}
            {toastMessage && (
                <Toast
                    message={toastMessage}
                    type={toastType}
                    onClose={() => setToastMessage(null)}
                />
            )}
        </div>
    );
}
