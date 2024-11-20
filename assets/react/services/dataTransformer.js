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
