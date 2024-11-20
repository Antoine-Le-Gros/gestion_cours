import React, { useState } from "react";

export default function PasswordChangeModal({ onClose, onPasswordChange }) {
    const [newPassword, setNewPassword] = useState("");

    const handleConfirm = () => {
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
