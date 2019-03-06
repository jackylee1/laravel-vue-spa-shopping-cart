export function updatePage(router, page) {
    router.replace({
        name: router.currentRoute.name,
        query: {page: page}
    });
}

export function currentPage(router) {
    return router.currentRoute.query.page;
}

export function getQuery(router, query) {
    return router.currentRoute.query[query];
}

export function updateQuery(router, query = {}) {
    router.replace({
        name: router.currentRoute.name,
        query: query
    });
}

export function getRouteByName(router, name) {
    let route = router.options.routes.find((route) => route.name === name);
    if (typeof route !== 'undefined') {
        return route;
    }
    route = router.options.routes.map(function(item) {
        if ('children' in item) {
            let indexInChildren = item.children.findIndex((itemChildren) => itemChildren.name === name);
            if (indexInChildren !== -1) {
                return item.children[indexInChildren];
            }
        }

    });
    route = route.filter(function (el) {
        return el != null;
    });

    return route[0];
}