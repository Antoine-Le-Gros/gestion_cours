import React, { useState } from "react";

export default function PasswordChangeModal({ onClose, onPasswordChange, onValidateOldPassword }) {
    const [oldPassword, setOldPassword] = useState("");
    const [newPassword, setNewPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");
    const [errorMessage, setErrorMessage] = useState("");

    const handleConfirm = async () => {
        if (!oldPassword) {
            setErrorMessage("Veuillez entrer votre ancien mot de passe.");
            return;
        }
        if (!newPassword || !confirmPassword) {
            setErrorMessage("Veuillez entrer et confirmer votre nouveau mot de passe.");
            return;
        }
        if (newPassword !== confirmPassword) {
            setErrorMessage("Les nouveaux mots de passe ne correspondent pas.");
            return;
        }
        setErrorMessage("");

        const isOldPasswordValid = await onValidateOldPassword(oldPassword);
        if (!isOldPasswordValid) {
            setErrorMessage("L'ancien mot de passe est incorrect.");
            return;
        }

        onPasswordChange(newPassword);
    };

    return (
        <div
            className="modal d-block"
            style={{
                backgroundColor: "rgba(0, 0, 0, 0.5)",
                top: "10%",
                display: "flex",
                alignItems: "center",
                justifyContent: "center",
            }}
        >
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header bg-black text-white">
                        <h5 className="modal-title">Changer de mot de passe</h5>
                        <button
                            type="button"
                            className="btn-close btn-close-white"
                            onClick={onClose}
                        ></button>
                    </div>
                    <div className="modal-body bg-light text-dark">
                        {errorMessage && (
                            <div className="alert alert-danger" role="alert">
                                {errorMessage}
                            </div>
                        )}
                        <div className="mb-3">
                            <label htmlFor="oldPassword" className="form-label">
                                Ancien mot de passe :
                            </label>
                            <input
                                type="password"
                                id="oldPassword"
                                className="form-control"
                                value={oldPassword}
                                onChange={(e) => setOldPassword(e.target.value)}
                            />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="newPassword" className="form-label">
                                Nouveau mot de passe :
                            </label>
                            <input
                                type="password"
                                id="newPassword"
                                className="form-control"
                                value={newPassword}
                                onChange={(e) => setNewPassword(e.target.value)}
                            />
                        </div>
                        <div className="mb-3">
                            <label htmlFor="confirmPassword" className="form-label">
                                Confirmer le nouveau mot de passe :
                            </label>
                            <input
                                type="password"
                                id="confirmPassword"
                                className="form-control"
                                value={confirmPassword}
                                onChange={(e) => setConfirmPassword(e.target.value)}
                            />
                        </div>
                    </div>
                    <div className="modal-footer bg-light">
                        <button className="btn btn-secondary" onClick={onClose}>
                            Annuler
                        </button>
                        <button className="btn btn-dark" onClick={handleConfirm}>
                            Confirmer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}
