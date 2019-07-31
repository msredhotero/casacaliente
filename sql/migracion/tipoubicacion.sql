INSERT INTO `casacaliente`.`tbtipoubicacion`
(`idtipoubicacion`,
`tipoubicacion`)

SELECT `tipusubicacio`.`ID TIPUSUBICACIO`,
    `tipusubicacio`.`TIPUSUBICACIO`
FROM `migracioncasacaliente`.`tipusubicacio`;

