/* eslint-disable no-use-before-define */
import { Request, Response } from "express";
import { getRepository } from "typeorm";
import { SolutionStatus, Task, TaskStatus } from "../entity/Task";
import { TaskSolution } from "../entity/TaskSolution";
import { User } from "../entity/User";
import { tasks } from "../resources/tasks";

const CHRISTMAS = 24;

class TasksController {
    public static listAll = async (req: Request, res: Response): Promise<void> => {
        const me = await getRepository(User).findOne(res.locals.jwtPayload.userId);
        const guesses = (await getRepository(TaskSolution).find({
            where: {
                user: me,
            },
        })) || [];

        res.send(tasks.map((t) => {
            t.status = getTaskStatus(t, 20);
            const guess = guesses.find((g) => g.day == t.day);
            if (guess) {
                t.guess = {
                    row: guess.row,
                    col: guess.col,
                };
            }
            if (t.status == TaskStatus.SOLVED) {
                t.solutionStatus = taskSolvedCorrectly(t)
                    ? SolutionStatus.CORRECT
                    : SolutionStatus.INCORRECT;
            } else {
                delete t.young.solutions;
                delete t.old.solutions;
            }
            return t;
        }));
    }
    public static getTask = async (req: Request, res: Response): Promise<void> => {
        const day = parseInt(req.params.day, 10);
        const t = tasks.find((ts) => ts.day == day);
        t.status = getTaskStatus(t, 20);
        if (t.status == TaskStatus.LOCKED) {
            res.status(401).send({ message: "Diese Aufgabe ist noch nicht freigeschalten!" });
            return;
        }
        try {
            const me = await getRepository(User).findOne(res.locals.jwtPayload.userId);
            const guess = await getRepository(TaskSolution).findOne({
                where: {
                    user: me,
                    day,
                },
            });
            if (guess) {
                t.guess = {
                    row: guess.row,
                    col: guess.col,
                };
            }
        } catch {
            //
        }
        if (t.status == TaskStatus.SOLVED) {
            t.solutionStatus = taskSolvedCorrectly(t)
                ? SolutionStatus.CORRECT
                : SolutionStatus.INCORRECT;
        } else {
            delete t.young.solutions;
            delete t.old.solutions;
        }
        res.send(t);
    }

    public static getImage = async (req: Request, res: Response): Promise<void> => {
        res.sendFile("E:/Downloads/ding_21.jpg");
    }

    public static saveSolution = async (req: Request, res: Response): Promise<void> => {
        const day = parseInt(req.params.day, 10);
        const t = tasks.find((ts) => ts.day == day);
        t.status = getTaskStatus(t, 15);
        if (t.status == TaskStatus.LOCKED) {
            res.status(401).send({ message: "Diese Aufgabe ist noch nicht freigeschalten!" });
            return;
        }
        if (t.status == TaskStatus.SOLVED) {
            res.status(401).send({ message: "Diese Aufgabe ist leider schon abgelaufen!" });
            return;
        }
        if (!(req.body.row && req.body.col)) {
            res.status(400).send({ message: "Nicht alle Felder wurden ausgefüllt!" });
            return;
        }

        const solutionRepository = getRepository(TaskSolution);
        const me = await getRepository(User).findOne(res.locals.jwtPayload.userId);
        let solution = await solutionRepository.findOne({
            where: {
                user: me,
                day,
            },
        });
        if (!solution) {
            solution = new TaskSolution();
            solution.user = me;
            solution.day = day;
        }
        solution.col = req.body.col;
        solution.row = req.body.row;
        try {
            await solutionRepository.save(solution);
        } catch {
            res.status(500).send({ message: "Error" });
            return;
        }
        res.send({ success: true });
    }
}

export default TasksController;

function taskSolvedCorrectly(t: Task) {
    if (!(t.guess?.row && t.guess?.col)) {
        return false;
    }
    return !!t.young.solutions.find((s) => s.row == t.guess.row && s.col == t.guess.col);
}

function getTaskStatus(task: Task, fakeTodayForTesting?: number): TaskStatus {
    const year = new Date().getFullYear();
    let now: Date;
    let todayDay: number;
    let date: Date;
    let month: number;
    if (fakeTodayForTesting) {
        now = new Date(year, 12, fakeTodayForTesting);
        todayDay = fakeTodayForTesting;
        date = new Date(year, 12, task.day);
        month = 12;
    } else {
        now = new Date();
        now.setHours(0, 0, 0, 0);
        todayDay = now.getDate();
        date = new Date(year, 12, task.day);
        month = new Date().getMonth();
    }

    const dayOfWeek = now.getDay();
    const dateDiff = date.getTime() - now.getTime();
    const diff = Math.floor(dateDiff / (60 * 60 * 24 * 1000));

    if ((todayDay == 25 || todayDay == 26 || todayDay == 27)
        && task.day == CHRISTMAS
        && month == 12) {
        return TaskStatus.OPEN;
    }

    enum DayOfWeek {
        SUNDAY = 0,
        MONDAY = 1,
        TUESDAY = 2,
        WEDNESDAY = 3,
        THURSDAY = 4,
        FRIDAY = 5,
        SATURDAY = 6,
    }

    let zurueckliegend = null;
    switch (dayOfWeek) {
    case DayOfWeek.SUNDAY:
        zurueckliegend = 3;
        break;
    case DayOfWeek.MONDAY:
        zurueckliegend = 4;
        break;
    case DayOfWeek.TUESDAY:
        zurueckliegend = 4;
        break;
    case DayOfWeek.WEDNESDAY:
        zurueckliegend = 2;
        break;
    case DayOfWeek.THURSDAY:
        zurueckliegend = 2;
        break;
    case DayOfWeek.FRIDAY:
        zurueckliegend = 2;
        break;
    case DayOfWeek.SATURDAY:
        zurueckliegend = 2;
        break;
    default:
        break;
    }
    if (-zurueckliegend < diff && diff <= 0) {
        return TaskStatus.OPEN;
    }
    if (diff > 0) {
        return TaskStatus.LOCKED;
    }
    // if (differance <= -zurueckliegend) {
    return TaskStatus.SOLVED;
    // }
}
