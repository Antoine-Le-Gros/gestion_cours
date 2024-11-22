import React, {useEffect, useState} from "react";
import { Chart } from "react-google-charts";
import { fetchAffecationByUserAndYear } from "../../services/api.js";
import {
    fromAffectationToHourlyVolumes,
    fromHourlyVolumesToWeeks,
    fromWeeksToData
} from "../../services/dataTransformer.js";
import Loading from "../Atomic/Loading.js";
import PropTypes from "prop-types";

export const options = {
    title: "Répartition des heures de cours par semestre",
    chartArea: { width: "70%" },
    hAxis: {
        title: "Nombre d'heures",

    },
    vAxis: {
        title: "Cours",
        minValue: 0,
    },
    isStacked: true,
};

export default function BarStacked({ userId, yearId}) {
    const [data, setData] = useState([]);
    const [isLoaded, setLoaded] = useState(false);

    useEffect(() => {
        fetchAffecationByUserAndYear(userId, yearId).then((response) => {
            console.log(response)
            setData(
                fromWeeksToData(
                    fromHourlyVolumesToWeeks(
                        fromAffectationToHourlyVolumes(response["hydra:member"])
                    )
                )
            );
            setLoaded(true);
        });
    }, []);

    return (
        <div>
            {isLoaded ?
            <Chart
                chartType="ColumnChart"
                width="100%"
                height="500px"
                data={data}
                options={options}
                legendToggle
            /> : <Loading />}
        </div>
    );
};

BarStacked.propTypes = {
    data: PropTypes.shape({
        userId: PropTypes.number.isRequired,
        yearId: PropTypes.number.isRequired,
    }).isRequired
};


