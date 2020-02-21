
update dblloguers l
    inner join (SELECT 
    dl.`ID LLOGUER`,
    SUM(dl.`PREU` * dl.`DIAS` / 7) AS tarifa
FROM `casacaliente02w`.`detall lloguer` dl
GROUP BY dl.`ID LLOGUER`) ddl on ddl.`ID LLOGUER` = l.idviejo
set l.total = ddl.tarifa

