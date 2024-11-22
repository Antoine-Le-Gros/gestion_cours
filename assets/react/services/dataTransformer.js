/**
 * Transform affectations in hourly volumes of structure : {week: week, semester: semester, volume: number}
 * @param affectations
 * @returns {*[]}
 */

export function fromAffectationToHourlyVolumes(affectations) {
    const volumes = [];
    affectations.forEach((affectation) => {
        // Multiply entry for multiple group assignations
        for (let i = 0; i < affectation.numberGroupTaken; i++) {
            const hourlyVolumes = affectation.course.hourlyVolumes;
            // Then Push the hourly volumes in the volumes array
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
            // Check if the week and semester already exists
            if (element.week === week && element.semester === semester) {
                element.volumes += volume.volume;
                find = true;
            }
        });

        // If not, create a new object and push it
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

    // Initialisation of the data with the header row
    data.push(["Nombre d'heures", ...semesters.map((semester) => `Semestre ${semester}`)]);

    // Fill the data with the weeks and the volumes
    weeks.forEach((week) => {
        let find = false
        const semesterNumber = data[0].indexOf(`Semestre ${week.semester}`);
        // Check if the week already exists
        data.forEach((element) => {
            if (element[0] === week.week.toString()) {
                if (element[semesterNumber] === undefined) {
                    element[semesterNumber] = week.volumes;
                } else {
                    element[semesterNumber] += week.volumes;
                }
                find = true;
            }
        });
        // If not, create a new line and push it
        if (find === false) {
            const line = [week.week.toString()];
            line[semesterNumber] = week.volumes;
            data.push(line);
        }
    });

    // If no affectation, add a line with "Aucune Affectation"
    if (semesters.length === 0) {
        data[0].push("Aucune Affectation");
        semesters.push("Aucune Affectation ");
    }

    // Add missing weeks and fill empty semesters after sorting
    //  permit to find the min and max for the next function
    console.log(data);
    data = fillMissingWeeks(sortDataByWeeksNumber(data));

    fillEmptySemester(data, semesters);


    // Sort again to send the data
    return sortDataByWeeksNumber(data);
}

/**
 * Function that Fill the table with missing weeks to avoid holes in the graph
 *
 * @param sortedData
 * @returns {*}
 */
function fillMissingWeeks(sortedData) {
    let min = 35;
    let max = 10;
    if (sortedData.length === 2) {
        if (sortedData[1][0] >= 35) {
            min = sortedData[1][0];
        } else {
            max = sortedData[1][0];
        }
    }
    if (!(sortedData.length <= 2)) {
        min = sortedData[1][0];
        max = sortedData[sortedData.length - 1][0];
    }

    // Find the min and max weeks

    // Fill the table with missing weeks from min to 52
    for (let i = min; i <= 52; i++) {
        if (sortedData.find((element) => element[0] === i.toString()) === undefined) {
            sortedData.push([i.toString()]);
        }
    }

    // Fill the table with missing weeks from 1 to max
    for (let i = 1; i <= max; i++) {
        if (sortedData.find((element) => element[0] === i.toString()) === undefined) {
            sortedData.push([i.toString()]);
        }
    }

    // Sort the data again
    return sortDataByWeeksNumber(sortedData);
}


/**
 * Function that Fill the table with 0 for the weeks, where no hours are affected
 *
 * @param data
 * @param semesters
 * @returns {*}
 */
function fillEmptySemester(data, semesters) {
    data.forEach((element) => {
        for (let i = 1; i <= semesters.length; i++) {
            // Fill the table with 0 for the weeks, where no hours are affected
            if (element[i] === undefined) {
                element[i] = 0;
            }
        }
    })

    return data;
}

/**
 * Function that sort the data by weeks number, starting by 35 and ending at 34 with a maximum of 52
 *
 * @param data
 * @returns {*[]}
 */
function sortDataByWeeksNumber(data) {
    if (data.length <= 2) return data;
    // Ignore the First Row to let the Header at the top
    const firstElement = data[0];
    const restOfArray = data.slice(1);

    // Sort the array by weeks number
    restOfArray.sort((a, b) => {
        if (a[0] >= 35 && b[0] >= 35) return a[0] - b[0];
        if (a[0] >= 35) return -1;
        if (b[0] >= 35) return 1;
        return a[0] - b[0];
    });

    // Return the sorted array with the header at the top
    return [firstElement, ...restOfArray];
}
