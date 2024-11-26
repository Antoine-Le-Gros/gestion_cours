import React from "react";
import PropTypes from "prop-types";
export default function TeacherItem({data = {}}){
    return (
        <a className="btn btn-outline-light w-100 py-5 text-center" href={`teacher/${data.id}`}>{String(data.firstname).charAt(0).toUpperCase() + String(data.firstname).slice(1)} {String(data.lastname).toUpperCase()}</a>
    );
}

TeacherItem.propTypes = {
    data: PropTypes.shape({
        firstname: PropTypes.string.isRequired,
        lastname: PropTypes.string.isRequired,
    }).isRequired
};
