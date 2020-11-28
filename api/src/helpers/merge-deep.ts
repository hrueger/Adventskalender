/* from https://stackoverflow.com/a/49727784/13485777 */
export function mergeDeep<T>(target: Partial<T>, ...sources: T[]): T {
    function isObject(item) {
        return (item && typeof item === "object" && !Array.isArray(item));
    }

    if (!sources.length) return target as T;
    const source = sources.shift();

    if (isObject(target) && isObject(source)) {
        for (const key in source) {
            if (isObject(source[key])) {
                if (!target[key]) {
                    Object.assign(target, { [key]: {} });
                } else {
                    target[key] = { ...target[key] };
                }
                mergeDeep(target[key], source[key]);
            } else {
                Object.assign(target, { [key]: source[key] });
            }
        }
    }

    return mergeDeep(target, ...sources);
}
