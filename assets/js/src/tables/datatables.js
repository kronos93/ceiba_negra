if ($('#users-table').length > 0) {
    (async() => {
        await
        import ( /* webpackChunkName: "datatables" */ '../configs/datatables')
        .catch(error => { console.log("Sucedio un error al importar el módulo de DataTables"); });
        await
        import ( /* webpackChunkName: "dt-users" */ './usuarios')
        .catch(error => { console.log("Sucedio un error al importar el módulo de Usuarios " + error); });
    })();
}

//Import script para dt de manzanas
if ($('#manzanas-table').length > 0) {
    (async() => {
        await
        import ( /* webpackChunkName: "datatables" */ '../configs/datatables')
        .catch(error => { console.log("Sucedio un error al importar el módulo de DataTables"); });
        await
        import ( /* webpackChunkName: "dt-manzanas" */ './manzanas')
        .catch(error => { console.log("Sucedio un error al importar el módulo de Manzanas " + error); });
    })();
}
//Import script para dt de huertos
if ($('#huertos-table').length > 0) {

    (async() => {
        await
        import ( /* webpackChunkName: "datatables" */ '../configs/datatables')
        .catch(error => { console.log("Sucedio un error al importar el módulo de DataTables"); });
        await
        import ( /* webpackChunkName: "dt-huertos" */ './huertos')
        .catch(error => { console.log("Sucedio un error al importar el módulo de Huertos " + error); });
    })();
}
//Import script para dt de opciones de pago
if ($('#opciones-pago-table').length > 0) {
    (async() => {
        await
        import ( /* webpackChunkName: "datatables" */ '../configs/datatables')
        .catch(error => { console.log("Sucedio un error al importar el módulo de DataTables"); });
        await
        import ( /* webpackChunkName: "dt-opciones-de-pago" */ './opciones_de_pago')
        .catch(error => { console.log("Sucedio un error al importar el módulo de Opciones de pago " + error); });
    })();
}
//Import script para dt de reservas eliminadas
if ($('#reservas-eliminadas-table').length > 0) {
    (async() => {
        await
        import ( /* webpackChunkName: "datatables" */ '../configs/datatables')
        .catch(error => { console.log("Sucedio un error al importar el módulo de DataTables"); });
        await
        import ( /* webpackChunkName: "dt-reservas-eliminadas" */ './reservas_eliminadas')
        .catch(error => { console.log("Sucedio un error al importar el módulo de reservas eliminadas " + error); });
    })();
}
//Import script para dt de reservas reservas
if ($('#reservas-table').length > 0) {
    (async() => {
        await
        import ( /* webpackChunkName: "datatables" */ '../configs/datatables')
        .catch(error => { console.log("Sucedio un error al importar el módulo de DataTables"); });
        await
        import ( /* webpackChunkName: "dt-reservas" */ './reservas')
        .catch(error => { console.log("Sucedio un error al importar el módulo de reservas " + error); });
    })();
}

if ($('#opciones-de-ingreso-table').length > 0) {
    (async() => {
        await
        import ( /* webpackChunkName: "datatables" */ '../configs/datatables')
        .catch(error => { console.log("Sucedio un error al importar el módulo de DataTables"); });
        await
        import ( /* webpackChunkName: "dt-opciones-ingreso" */ './opciones_ingreso')
        .catch(error => { console.log("Sucedio un error al importar el módulo de opciones de ingreso " + error); });
    })();
}
//Import script para dt de ingresos /bancos/caja
if ($('#historial-ingresos-table').length > 0) {
    (async() => {
        await
        import ( /* webpackChunkName: "datatables" */ '../configs/datatables')
        .catch(error => { console.log("Sucedio un error al importar el módulo de DataTables"); });
        await
        import ( /* webpackChunkName: "dt-historial-ingresos" */ './historial_ingresos')
        .catch(error => { console.log("Sucedio un error al importar el módulo de historial de ingresos " + error); });
    })();
}
if ($('#historial-ventas-table').length > 0) {
    (async() => {
        await
        import ( /* webpackChunkName: "datatables" */ '../configs/datatables')
        .catch(error => { console.log("Sucedio un error al importar el módulo de DataTables"); });
        await
        import ( /* webpackChunkName: "dt-historial-ventas" */ './historial_ventas')
        .catch(error => { console.log("Sucedio un error al importar el módulo de historial de ventas" + error); });
    })();
}
if ($('#pagos-table').length > 0) {
 
    (async() => {
        await
        import ( /* webpackChunkName: "datatables" */ '../configs/datatables')
        .catch(error => { console.log("Sucedio un error al importar el módulo de DataTables"); });
        await
        import ( /* webpackChunkName: "dt-pagos" */ './pagos')
        .catch(error => { console.log("Sucedio un error al importar el módulo de pagos" + error); });
    })();
}
if ($('#comisiones-per-lider-table').length > 0) {
    (async() => {
        await
        import ( /* webpackChunkName: "datatables" */ '../configs/datatables')
        .catch(error => { console.log("Sucedio un error al importar el módulo de DataTables"); });
        await
        import ( /* webpackChunkName: "dt-comisiones-por-lider" */ './comisiones_per_lider')
        .catch(error => { console.log("Sucedio un error al importar el módulo de comisiones por lider" + error); });
    })();
}