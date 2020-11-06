import { Request, Response } from "express";
import { Task, TaskStatus } from "../entity/Task";
import { tasks } from "../resources/tasks";

const CHRISTMAS = 24;

class TasksController {
    public static listAll = async (req: Request, res: Response): Promise<void> => {
        res.send(tasks.map((t) => {
            // eslint-disable-next-line no-use-before-define
            t.status = getTaskStatus(t);
            if (t.status !== TaskStatus.SOLVED) {
                delete t.easy.solution;
                delete t.hard.solution;
            }
            return t;
        }));
    }
}

export default TasksController;

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
