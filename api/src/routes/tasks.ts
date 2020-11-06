import { Router } from "express";
import TasksController from "../controllers/TasksController";

const router = Router();
router.get("/", TasksController.listAll);

export default router;
