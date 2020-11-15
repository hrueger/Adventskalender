type TaskSolution = {
    row: 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9;
    column: "A" | "B" | "C" | "D" | "E" | "F" | "G" | "H";
}

export type Task = {
    day: number;
    status?: TaskStatus;
    solutionStatus?: SolutionStatus;
    young: {
        description: string;
        solution: TaskSolution;
    };
    old: {
        description: string;
        solution: TaskSolution;
    };
};

export enum TaskStatus {
    LOCKED = "LOCKED",
    OPEN = "OPEN",
    SOLVED = "SOLVED",
}

export enum SolutionStatus {
    CORRECT = "CORRECT",
    INCORRECT = "INCORRECT",
}
