import { Router } from "express";
import TasksController from "../controllers/TasksController";

const router = Router();
router.get("/", TasksController.kiosk);

export default router;
