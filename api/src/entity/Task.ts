export type Field = {
    row: 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9;
    column: "A" | "B" | "C" | "D" | "E" | "F" | "G" | "H";
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
