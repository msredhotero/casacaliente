INSERT INTO `casacaliente_05122019`.`dbubicaciones`
(`idubicacion`,
`dormitorio`,
`color`,
`reftipoubicacion`,
`codapartament`,
`hutg`)

SELECT u.`ID UBICACIO`,
    u.`DORMITORIS`,
    u.`COLOR`,
    tt.idtipoubicacion,
    /*u.`ID TIPUSUBICACIO`,*/
    u.`COD APARTAMENT`,
    u.`HUTG`
FROM `casacalientew`.`ubicacions` u
inner join tbtipoubicacion tt on tt.idviejo = u.`ID TIPUSUBICACIO`

