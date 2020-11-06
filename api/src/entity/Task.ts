export type Task = {
    day: number;
    status?: TaskStatus;
    solutionStatus?: SolutionStatus;
    easy: {
        description: string;
        solution: string;
    };
    hard: {
        description: string;
        solution: string;
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
