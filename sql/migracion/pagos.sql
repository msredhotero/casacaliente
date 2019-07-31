INSERT INTO `casacaliente`.`dbpagos`
(`idpago`,
`reflloguers`,
`refformaspagos`,
`cuota`,
`monto`,
`taxa`,
`fechapago`,
`fecha`,
`usuario`,
`cancelado`)
SELECT `pagaments`.`ID PAGAMENT`,
    `pagaments`.`ID LLOGUER`,
    `pagaments`.`ID_FORMAPAG`,
    `pagaments`.`PAGAMENT`,
    `pagaments`.`PAGAMENT`,
    `pagaments`.`TAXA`,
    `pagaments`.`DATA PAGAMENT`,
    `pagaments`.`DATA PAGAMENT`,
    'marcos migracion',
    0
FROM `migracioncasacaliente`.`pagaments`;

