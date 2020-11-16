export type Field = {
    row: number;
    col: string;
}

export type Task = {
    day: number;
    status?: TaskStatus;
    solutionStatus?: SolutionStatus;
    guess?: Field;
    young: {
        description: string;
        solutions: Field[];
    };
    old: {
        description: string;
        solutions: Field[];
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
