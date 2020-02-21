INSERT INTO `casacaliente_022020`.`dbubicaciones`
(`idubicacion`,
`dormitorio`,
`color`,
`reftipoubicacion`,
`codapartament`,
`hutg`)

SELECT 
u.`ID UBICACIO`,
    u.`DORMITORIS`,
    u.`COLOR`,
    tt.idtipoubicacion,
    /*u.`ID TIPUSUBICACIO`,*/
    u.`COD APARTAMENT`,
    u.`HUTG`
FROM `casacaliente02w`.`ubicacions` u
inner join tbtipoubicacion tt on tt.idtipoubicacion = u.`ID TIPUSUBICACIO`
where u.`ID TIPUSUBICACIO` is not null

/* inserto el id ubicacio porque no existe en la otra table */

