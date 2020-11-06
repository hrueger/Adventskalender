import { Task } from "../entity/Task";

export const tasks: Task[] = [
    {
        day: 1,
        easy: {
            description: "Test am Tag 1 leicht",
            solution: "leicht1",
        },
        hard: {
            description: "Test am Tag 1 schwer",
            solution: "schwer1",
        },
    },
    {
        day: 2,
        easy: {
            description: "Test am Tag 2 leicht",
            solution: "leicht2",
        },
        hard: {
            description: "Test am Tag 2 schwer",
            solution: "schwer2",
        },
    },
    {
        day: 3,
        easy: {
            description: "Test am Tag 3 leicht",
            solution: "leicht3",
        },
        hard: {
            description: "Test am Tag 3 schwer",
            solution: "schwer3",
        },
    },
];
