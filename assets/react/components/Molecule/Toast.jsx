import React from "react";

export default function Toast({ message, type, onClose }) {
    return (
        <div
            className={`toast align-items-center text-white ${
                type === "success" ? "bg-success" : "bg-danger"
            } position-fixed top-0 end-0 m-3 show`}
            role="alert"
            aria-live="assertive"
            aria-atomic="true"
            style={{ zIndex: 1050 }}
        >
            <div className="d-flex">
                <div className="toast-body">{message}</div>
                <button
                    type="button"
                    className="btn-close btn-close-white me-2 m-auto"
                    aria-label="Close"
                    onClick={onClose}
                ></button>
            </div>
        </div>
    );
}
