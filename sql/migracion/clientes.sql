INSERT INTO `casacaliente`.`dbclientes`
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
`email2`)
SELECT `clients`.`ID CLIENT`,
    `clients`.`COGNOM`,
    `clients`.`NOM`,
    `clients`.`NIF`,
    `clients`.`CARRER`,
    `clients`.`CODI POSTAL`,
    `clients`.`CIUTAT`,
    `clients`.`PAIS`,
    `clients`.`TELEFON`,
    `clients`.`EMAIL`,
    `clients`.`COMENTARIS`,
    `clients`.`TELEFON 2`,
    `clients`.`EMAIL 2`
FROM `migracioncasacaliente`.`clients`;

