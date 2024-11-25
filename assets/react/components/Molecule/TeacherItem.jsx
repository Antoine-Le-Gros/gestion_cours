import React from "react";
import PropTypes from "prop-types";
export default function TeacherItem({data = {}}){
    return (
        <button className="btn btn-outline-light w-100 py-5 text-center">{data.firstname} {data.lastname}</button>
    );
}

TeacherItem.propTypes = {
    data: PropTypes.shape({
        firstname: PropTypes.string.isRequired,
        lastname: PropTypes.string.isRequired,
    }).isRequired
};
