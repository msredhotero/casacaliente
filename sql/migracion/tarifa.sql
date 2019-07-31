INSERT INTO `casacaliente`.`dbtarifas`
(`idtarifa`,
`tarifa`,
`reftipoubicacion`,
`refperiodos`)

SELECT `tarifa`.`ID TARIFA`,
    `tarifa`.`TARIFA`,
    `tarifa`.`ID TIPUSUBICACIO`,
    `tarifa`.`ID PERIODE`
FROM `migracioncasacaliente`.`tarifa`;
