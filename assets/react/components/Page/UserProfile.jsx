import React, { useEffect, useState } from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import { fetchMe } from "../../services/api.js";

export default function UserProfile() {
    const [user, setUser] = useState({});

    useEffect(() => {
        fetchMe().then((data) => {
            setUser(data || {});
        });
    }, []);

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
                    {/* Ligne 1 : Identifiant, Nom, et Prénom */}
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
                    {/* Ligne 2 : Statut d'activité */}
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
                            <button className="btn btn-dark text-white">
                                Changer de mot de passe
                            </button>
                        </div>
                    </div>
                    {/* Ligne 3 : Limite d'heures */}
                    <div className="row mb-4">
                        <div className="col-12">
                            <h5 className="text-secondary">Heures maximales :</h5>
                            <div
                                className="d-flex align-items-center justify-content-start"
                                style={{
                                    border: "1px solid #dee2e6",
                                    borderRadius: "8px",
                                    padding: "10px",
                                    backgroundColor: "#e9ecef",
                                }}
                            >
                                <div
                                    style={{
                                        fontSize: "2.5rem",
                                        fontWeight: "bold",
                                        color: "#495057",
                                        marginRight: "10px",
                                    }}
                                >
                                    {user.hoursMax || "N/A"}
                                </div>
                                <div className="text-muted" style={{ fontSize: "1rem" }}>
                                    heures autorisées maximum
                                </div>
                            </div>
                        </div>
                    </div>
                    {/* Ligne 4 : Citation inspirante */}
                    <div className="row mt-5">
                        <div className="col-12 text-center">
                            <blockquote className="blockquote text-center">
                                <p className="mb-0 fst-italic text-muted">
                                    "Le véritable voyage de découverte ne consiste pas à chercher de nouveaux paysages, mais à avoir de nouveaux yeux." – Marcel Proust
                                </p>
                                <footer className="blockquote-footer mt-2">Une pensée pour l'utilisateur</footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
