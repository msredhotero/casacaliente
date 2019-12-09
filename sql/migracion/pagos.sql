INSERT INTO `casacaliente_05122019`.`dbpagos`
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
SELECT p.`ID PAGAMENT`,
    p.`ID LLOGUER`,
    p.`ID_FORMAPAG`,
    p.`PAGAMENT`,
    p.`PAGAMENT`,
    (case when p.`TAXA` = 1 then pe.taxaturistica else 0 end),
    p.`DATA PAGAMENT`,
    p.`DATA PAGAMENT`,
    'marcos migracion',
    0
FROM `casacalientem`.`pagaments` p
left join casacaliente_05122019.dblloguersadicional pe on pe.reflloguers = p.`ID LLOGUER`

