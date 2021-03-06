import { Router } from "express";
import TasksController from "../controllers/TasksController";
import { checkJwt } from "../middlewares/checkJwt";

const router = Router();
router.get("/", [checkJwt], TasksController.listAll);
router.get("/:day", [checkJwt], TasksController.getTask);
router.get("/images/:day", [checkJwt], TasksController.getImage);
router.post("/:day", [checkJwt], TasksController.saveSolution);

export default router;
