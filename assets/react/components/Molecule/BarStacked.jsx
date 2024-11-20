import React, {useEffect, useState} from "react";
import { Chart } from "react-google-charts";
import { fetchAffecationByUserAndYear } from "../../services/api.js";
import {
    fromAffectationToHourlyVolumes,
    fromHourlyVolumesToWeeks,
    fromWeeksToData
} from "../../services/dataTransformer.js";

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

export default function BarStacked() {

    const [data, setData] = useState([]);

    useEffect(() => {
        fetchAffecationByUserAndYear(8, 1).then((response) => {
            setData(
                fromWeeksToData(
                    fromHourlyVolumesToWeeks(
                        fromAffectationToHourlyVolumes(response["hydra:member"])
                    )
                )
            );
        });
    }, []);

    return (
        <div>
            <Chart
                chartType="ColumnChart"
                width="100%"
                height="500px"
                data={data}
                options={options}
                legendToggle
            />
        </div>
    );
};


