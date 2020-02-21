INSERT INTO `casacaliente_022020`.`dbclientes`
(`idcliente`,
`cognom`,
`nom`,
`nif`,
`carrer`,
`codipostal`,
`ciutat`,
`pais`,
`telefon`,
`email`,
`comentaris`,
`telefon2`,
`email2`,
reflocatarios,
idclientew)
SELECT '',
/*`clientsw`.`ID CLIENT`,*/
    `clientsw`.`COGNOM`,
    `clientsw`.`NOM`,
    `clientsw`.`NIF`,
    `clientsw`.`CARRER`,
    `clientsw`.`CODI POSTAL`,
    `clientsw`.`CIUTAT`,
    `clientsw`.`PAIS`,
    `clientsw`.`TELEFON`,
    `clientsw`.`EMAIL`,
    `clientsw`.`COMENTARIS`,
    `clientsw`.`TELEFON 2`,
    `clientsw`.`EMAIL 2`,
    4,
    `clientsw`.`ID CLIENT`
FROM `casacaliente02w`.`clientsw`;

