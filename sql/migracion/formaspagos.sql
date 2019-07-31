INSERT INTO `casacaliente`.`tbformaspagos`
(`idformapago`,
`abreviatura`,
`formapago`)
SELECT `formes_de_pagament`.`ID_FORMAPAG`,
    `formes_de_pagament`.`INICIALS`,
    `formes_de_pagament`.`NOM`
FROM `migracioncasacaliente`.`formes_de_pagament`;

