import { Task } from "../entity/Task";

export const tasks: Task[] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12,
    13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24].map((n) => ({
    day: n,
    young: {
        description: `Test am Tag ${n} leicht`,
        solution: {
            row: 5,
            column: "E",
        },
    },
    old: {
        description: `Test am Tag ${n} schwer`,
        solution: {
            row: 5,
            column: "E",
        },
    },
}));
