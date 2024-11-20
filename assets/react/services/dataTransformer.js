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
