export function fromAffectationToHourlyVolumes(affectations) {
    const volumes = [];
    affectations.forEach((affectation) => {
        for (let i = 0; i < affectation.numberGroupTaken; i++) {
            const hourlyVolumes = affectation.course.hourlyVolumes;
            hourlyVolumes.forEach((hourlyVolume) => {
                volumes.push({
                    week: hourlyVolume.week,
                    semester: hourlyVolume.semester,
                    volume: hourlyVolume.volume
                });
            })
        }
    })
    return volumes;
}

export function fromHourlyVolumesToWeeks(volumes) {
    const weeks = [];
    volumes.forEach((volume) => {
        const week = volume.week.number;
        const semester = volume.week.semesters.number;
        let find = false;

        weeks.forEach((element) => {
            if (element.week === week && element.semester === semester) {
                element.volumes += volume.volume;
                find = true;
            }
        });

        if (find === false) {
            weeks.push({
                week: week,
                semester: semester,
                volumes: volume.volume
            });
        }

    });
    return Object.values(weeks);
}

export function fromWeeksToData(weeks) {
    let data = [];
    const semesters = [];
    weeks.forEach((week) => {
        if (!semesters.includes(week.semester)) {
            semesters.push(week.semester);
        }
    })

    data.push(["Nombre d'heures", ...semesters.map((semester) => `Semestre ${semester}`)]);

    weeks.forEach((week) => {
        let find = false
        data.forEach((element) => {
            if (element[0] === week.week.toString()) {
                if (element[week.semester] === undefined) {
                    element[week.semester] = week.volumes;
                } else {
                    element[week.semester] += week.volumes;
                }
                find = true;
            }
        });
        if (find === false) {
            const line = [week.week.toString()];
            line[week.semester] = week.volumes;
            data.push(line);
        }
    });


    data = fillMissingWeeks(sortDataByWeeksNumber(data));
    console.log(data);

    fillEmptySemester(data, semesters);

    return sortDataByWeeksNumber(data);
}
