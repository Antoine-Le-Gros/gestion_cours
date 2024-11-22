import React from "react";
import PropTypes from "prop-types";
export default function TeacherItem({data = {}}){
    return (
        <button className="btn btn-outline-light mb-3 w-50">{data.firstname} {data.lastname}</button>
    );
}

TeacherItem.propTypes = {
    data: PropTypes.shape({
        firstname: PropTypes.string.isRequired,
        lastname: PropTypes.string.isRequired,
    }).isRequired
};
