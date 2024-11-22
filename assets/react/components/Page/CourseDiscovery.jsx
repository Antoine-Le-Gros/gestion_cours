import React, { useEffect, useState } from "react";
import Loading from "../Atomic/Loading.js";
import {fetchCourseTitleInformation, fetchTags} from "../../services/api.js";
import CourseTitleItem from "../Molecule/CourseTitleItem.js";

export default function CourseDiscovery() {
    const [selectedTag, setSelectedTag] = useState(0);
    const [courseData, setCourseData] = useState([]);
    const [tags, setTags] = useState([]);
    const [search, setSearch] = useState('');
    const [semester, setSemester] = useState(1);

    useEffect(() => {
        fetchTags().then((data) => {
            setTags(data['hydra:member']);
        });
    }, []);
    useEffect(() => {
        fetchCourseTitleInformation(search,semester, selectedTag ?? 0).then((data) => {
            setCourseData(data['hydra:member']);
        });
    }, [selectedTag, search, semester]);
    return (
        <div>
            <h1>Cours présents cette année</h1>
            <div className="d-flex flex-row justify-content-center gap-3">
                <button
                    className="btn btn-dark"
                    onClick={() => setSemester(semester - 1)}
                    disabled={semester === 1}>
                    <i className="bi bi-0-circle">Précédent</i>
                </button>
                <p>S{semester}</p>
                <button
                    className="btn btn-dark"
                    onClick={() => setSemester(semester + 1)}
                    disabled={semester === 6}>
                    <i className="bi bi-arrow-right">Suivant</i></button>
            </div>
            <div className="input-group mb-4">
                <select className="form-select bg-dark text-white" value={selectedTag}
                        onChange={(e) => setSelectedTag(e.target.value)}>
                    <option value="0">Aucun</option>
                    {tags.map((tag) => (
                        <option value={tag.id}>{tag.name}</option>
                    ))}
                </select>
                <input
                    type="text"
                    className="form-control bg-dark text-white"
                    placeholder="Rechercher..."
                    onChange={(e) => setSearch(e.target.value)}
                />
                <button className="btn btn-outline-light">
                    Rechercher
                </button>
            </div>
            {courseData.length === 0 ? (
                <Loading/>
            ) : (
                <section className="d-flex flex-column align-items-center">
                    {courseData?.map((course) => (
                        <CourseTitleItem key={course.id} data={course}>{course.name} </CourseTitleItem>
                    ))}
                </section>
            )}
        </div>
    );
}