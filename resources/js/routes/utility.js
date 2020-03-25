const lazy = (component) => {
    return () => import(/* webpackChunkName: '' */ `@/components/views/${component}`).then(c => c.default || c)
};

export {lazy}