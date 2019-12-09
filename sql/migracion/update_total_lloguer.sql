
update dblloguers l
    inner join (SELECT 
    dl.`ID LLOGUER`,
    SUM(dl.`PREU` * dl.`DIAS` / 7) AS tarifa
FROM `casacalientem`.`detall lloguer` dl
GROUP BY dl.`ID LLOGUER`) ddl on ddl.`ID LLOGUER` = l.idlloguer
set l.total = ddl.tarifa

