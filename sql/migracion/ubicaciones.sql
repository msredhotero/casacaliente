INSERT INTO `casacaliente`.`dbubicaciones`
(`idubicacion`,
`dormitorio`,
`color`,
`reftipoubicacion`,
`codapartament`,
`hutg`)

SELECT `ubicacions`.`ID UBICACIO`,
    `ubicacions`.`DORMITORIS`,
    `ubicacions`.`COLOR`,
    `ubicacions`.`ID TIPUSUBICACIO`,
    `ubicacions`.`COD APARTAMENT`,
    `ubicacions`.`HUTG`
FROM `migracioncasacaliente`.`ubicacions`;

