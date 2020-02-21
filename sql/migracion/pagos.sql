INSERT INTO `casacaliente_022020`.`dbpagos`
(`idpago`,
`reflloguers`,
`refformaspagos`,
`cuota`,
`monto`,
`taxa`,
`fechapago`,
`fecha`,
`usuario`,
`cancelado`,
idviejo)
SELECT '',
	ll.idlloguer,
    /*p.`ID LLOGUER`,*/
    p.`ID_FORMAPAG`,
    p.`PAGAMENT`,
    p.`PAGAMENT`,
    (case when p.`TAXA` = 1 then pe.taxaturistica else 0 end),
    p.`DATA PAGAMENT`,
    p.`DATA PAGAMENT`,
    'marcos migracion w',
    0,
    p.`ID PAGAMENT`
FROM `casacaliente02w`.`pagaments` p
inner join dblloguers ll on ll.idviejo = p.`ID LLOGUER`
/*inner join dblloguers ll on ll.idlloguer = p.`ID LLOGUER`*/
left join casacaliente_022020.dblloguersadicional pe on pe.reflloguers = ll.idlloguer

