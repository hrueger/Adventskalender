import { Router } from "express";
import TasksController from "../controllers/TasksController";
import { checkJwt } from "../middlewares/checkJwt";

const router = Router();
router.get("/", [checkJwt], TasksController.listAll);

export default router;
