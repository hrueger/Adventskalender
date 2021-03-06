
/*    +-----------------------------------------------------------------------+    */
/*    |    Do not edit this file directly.                                    |    */
/*    |    It was copied by redundancyJS.                                     |    */
/*    |    To modify it, first edit the source file (see redundancy.json).    |    */
/*    |    Then, run "npx redundancyjs" in the terminal.                      |    */
/*    +-----------------------------------------------------------------------+    */

/* do not edit */ /* from https://stackoverflow.com/a/49727784/13485777 */
/* do not edit */ export function mergeDeep<T>(target: Partial<T>, ...sources: T[]): T {
/* do not edit */     function isObject(item) {
/* do not edit */         return (item && typeof item === "object" && !Array.isArray(item));
/* do not edit */     }
/* do not edit */ 
/* do not edit */     if (!sources.length) return target as T;
/* do not edit */     const source = sources.shift();
/* do not edit */ 
/* do not edit */     if (isObject(target) && isObject(source)) {
/* do not edit */         for (const key in source) {
/* do not edit */             if (isObject(source[key])) {
/* do not edit */                 if (!target[key]) {
/* do not edit */                     Object.assign(target, { [key]: {} });
/* do not edit */                 } else {
/* do not edit */                     target[key] = { ...target[key] };
/* do not edit */                 }
/* do not edit */                 mergeDeep(target[key], source[key]);
/* do not edit */             } else {
/* do not edit */                 Object.assign(target, { [key]: source[key] });
/* do not edit */             }
/* do not edit */         }
/* do not edit */     }
/* do not edit */ 
/* do not edit */     return mergeDeep(target, ...sources);
/* do not edit */ }
/* do not edit */