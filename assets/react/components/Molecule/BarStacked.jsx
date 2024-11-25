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
    title: "Répartition des heures de cours par semaine",
    titleTextStyle: {color: '#ffffff'},
    chartArea: { width: "70%" },
    hAxis: {
        title: "Semaines",
        titleTextStyle: {color: '#ffffff'},
        textStyle: {color: '#ffffff'},

    },
    vAxis: {
        title: "Nombre d'heures de cours",
        titleTextStyle: {color: '#ffffff'},
        textStyle: {color: '#ffffff'},
        minValue: 0,
        format: '0',
    },
    isStacked: true,
    backgroundColor: { fill:'transparent' },
    legendTextStyle: { color: '#ffffff' },
};

export default function BarStacked({ userId, yearId}) {
    const [data, setData] = useState([]);
    const [isLoaded, setLoaded] = useState(false);

    useEffect(() => {
        setLoaded(false);
        fetchAffecationByUserAndYear(userId, yearId).then((response) => {
            setData(
                fromWeeksToData(
                    fromHourlyVolumesToWeeks(
                        fromAffectationToHourlyVolumes(response["hydra:member"])
                    )
                )
            );
            setLoaded(true);
        });
    }, [yearId]);

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
    userId: PropTypes.number.isRequired,
    yearId: PropTypes.number.isRequired,
};


