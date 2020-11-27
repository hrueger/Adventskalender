import { Router } from "express";
import UserController from "../controllers/UserController";
import { checkForAdmin } from "../middlewares/checkForAdmin";
import { checkJwt } from "../middlewares/checkJwt";

const router = Router();
router.get("/", UserController.listUsers);
router.post("/", UserController.newUser);
router.post("/:id/admin", [checkJwt, checkForAdmin], UserController.changeAdminStatus);
router.get("/admin", [checkJwt, checkForAdmin], UserController.listUsersAdmin);
router.delete("/:id", [checkJwt, checkForAdmin], UserController.deleteUser);

export default router;
