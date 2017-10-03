if ($('#users-table').length > 0) {
    import( /* webpackChunkName: "datatables" */ '../configs/datatables')
    .then( () => { return import( /* webpackChunkName: "dt_users" */ './usuarios'); })
    .catch(error => { console.log("Sucedio un error al importar el módulo error: " + error); });
}
//Import script para dt de manzanas
if ($('#manzanas-table').length > 0) {
    import( /* webpackChunkName: "datatables" */ '../configs/datatables')
    .then( () => { return import( /* webpackChunkName: "dt_manzanas" */ './manzanas'); })
    .catch(error => { console.log("Sucedio un error al importar el módulo error: " + error); });
}
//Import script para dt de huertos
if ($('#huertos-table').length > 0) {
    import( /* webpackChunkName: "datatables" */ '../configs/datatables')
    .then( () => { return import( /* webpackChunkName: "dt_huertos" */ './huertos'); })
    .catch(error => { console.log("Sucedio un error al importar el módulo error: " + error); });
}
//Import script para dt de opciones de pago
if ($('#opciones-pago-table').length > 0) {
    import( /* webpackChunkName: "datatables" */ '../configs/datatables')
    .then( () => { return import(  /* webpackChunkName: "dt_opciones_de_pago" */ './opciones_de_pago'); })
    .catch(error => { console.log("Sucedio un error al importar el módulo error: " + error); });
}
//Import script para dt de reservas eliminadas
if ($('#reservas-eliminadas-table').length > 0) {
    import( /* webpackChunkName: "datatables" */ '../configs/datatables')
    .then( () => { return import(/* webpackChunkName: "dt_reservas_eliminadas" */ './reservas_eliminadas'); })
    .catch(error => { console.log("Sucedio un error al importar el módulo error: " + error); });
}
//Import script para dt de reservas reservas
if ($('#reservas-table').length > 0) {
    import( /* webpackChunkName: "datatables" */ '../configs/datatables')
    .then( () => { return import(/* webpackChunkName: "dt_reservas" */ './reservas'); })
    .catch(error => { console.log("Sucedio un error al importar el módulo error: " + error); });
}

if ($('#opciones-de-ingreso-table').length > 0) {
    import( /* webpackChunkName: "datatables" */ '../configs/datatables')
    .then( () => { return import(/* webpackChunkName: "dt_opciones_ingreso" */ './opciones_ingreso'); })
    .catch(error => { console.log("Sucedio un error al importar el módulo error: " + error); });
}
//Import script para dt de ingresos /bancos/caja
if ($('#historial-ingresos-table').length > 0) {
    import( /* webpackChunkName: "datatables" */ '../configs/datatables')
    .then( () => { return import(/* webpackChunkName: "dt_historial_ingresos" */ './historial_ingresos'); })
    .catch(error => { console.log("Sucedio un error al importar el módulo error: " + error); });
}
if ($('#historial-ventas-table').length > 0) {
    import( /* webpackChunkName: "datatables" */ '../configs/datatables')
    .then( () => { return import(/* webpackChunkName: "dt_historial_ventas" */ './historial_ventas'); })
    .catch(error => { console.log("Sucedio un error al importar el módulo error: " + error); });
}
if ($('#pagos-table').length > 0) {
    import( /* webpackChunkName: "datatables" */ '../configs/datatables')
    .then( () => { return import(/* webpackChunkName: "dt_pagos" */ './pagos'); })
    .catch(error => { console.log("Sucedio un error al importar el módulo error: " + error); });
}
if ($('#comisiones-per-lider-table').length > 0) {
    import( /* webpackChunkName: "datatables" */ '../configs/datatables')
    .then( () => { return import(/* webpackChunkName: "dt_comisiones_por_lider" */ './comisiones_per_lider'); })
    .catch(error => { console.log("Sucedio un error al importar el módulo error: " + error); });
}