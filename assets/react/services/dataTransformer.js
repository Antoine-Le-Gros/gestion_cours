/**
 * Transform affectations in hourly volumes of structure : {week: week, semester: semester, volume: number}
 * @param affectations
 * @returns {*[]}
 */

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


/**
 * Transform hourly volumes in weeks of structure : {week: number, semester: number, volumes: number}
 * It also permits to sum volumes of the same week and semester into only one object
 *
 * @param volumes
 * @returns {*[]}
 */
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


/**
 * Transform weeks in data of structure : [week, semester1, semester2, ...]
 * Also add a header row containg informations of the Graph
 *
 * @param weeks
 * @returns {*}
 */
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

function fillMissingWeeks(sortedData) {
    const min = sortedData[1][0];
    const max = sortedData[sortedData.length - 1][0];

    for (let i = min; i <= 52; i++) {
        if (sortedData.find((element) => element[0] === i.toString()) === undefined) {
            sortedData.push([i.toString()]);
        }
    }

    for (let i = 1; i <= max; i++) {
        if (sortedData.find((element) => element[0] === i.toString()) === undefined) {
            sortedData.push([i.toString()]);
        }
    }

    return sortDataByWeeksNumber(sortedData);
}

function fillEmptySemester(data, semesters) {
    data.forEach((element) => {
        for (let i = 1; i <= semesters.length; i++) {
            if (element[i] === undefined) {
                element[i] = 0;
            }
        }
    })

    return data;
}

function sortDataByWeeksNumber(data) {
    const firstElement = data[0];
    const restOfArray = data.slice(1);

    restOfArray.sort((a, b) => {
        if (a[0] >= 35 && b[0] >= 35) return a[0] - b[0];
        if (a[0] >= 35) return -1;
        if (b[0] >= 35) return 1;
        return a[0] - b[0];
    });

    return [firstElement, ...restOfArray];
}
