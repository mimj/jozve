const findRecursive = (id, page) => {
    // debugger
    if (page.id === id) {
        return page
    }

    if(page.hasOwnProperty('pages')){
        if (page.pages.length > 0) {
            for (let i = 0; i < page.pages.length; i++) {
                let p = page.pages[i], result;
                result = findRecursive(id, p);
                if (result) {
                    return result;
                    break
                }
            }
        }
    }
    return false;
};

export default findRecursive